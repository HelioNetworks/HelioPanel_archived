<?php

namespace HelioNetworks\HelioPanelBundle\Controller;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use HelioNetworks\HelioPanelBundle\Entity\Hook;
use HelioNetworks\HelioPanelBundle\HTTP\Request;
use HelioNetworks\HelioPanelBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use HelioNetworks\HelioPanelBundle\Form\Type\SetActiveAccountRequestType;
use HelioNetworks\HelioPanelBundle\Form\Model\SetActiveAccountRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use HelioNetworks\HelioPanelBundle\Form\Type\AccountType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use HelioNetworks\HelioPanelBundle\Entity\Account;

/**
 * Account Controller.
 *
 * @Route("/account")
 */
class AccountController extends HelioPanelAbstractController
{
    /**
    * Lists all Account entities.
    *
    * @Route("/", name="account")
    * @Template()
    */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $this->getUser()->getAccounts();

        return array('entities' => $entities);
    }

    /**
    * Deletes a Account entity.
    *
    * @Route("/{id}/delete", name="account_delete")
    */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('HelioNetworksHelioPanelBundle:Account')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Account entity.');
        }

        if (!$this->getUser()->getAccounts()->contains($entity)) {
            throw new AccessDeniedHttpException();
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('account'));
    }

    /**
     * Creates an FOS User with the given username and password.
     *
     * @param string $username The username of the user
     * @param string $password The password of the user
     */
    protected function createUser($username, $password)
    {
        $user = new User();
        $user->setPlainPassword($password);
        $user->setUsername($username);
        $user->setEmail(uniqid().'@heliohost.org');
        $user->setEnabled(true);

        $this->get('fos_user.user_manager')
            ->updateUser($user);

        return $user;
    }

    /**
     * Create a new user based from a cPanel account.
     *
     * @Route("/createUser", name="account_create_user")
     * @Method({"POST"})
     * @Template()
     */
    public function createUserAction() {
        $account = new Account();
        $form = $this->createForm(new AccountType(), $account);

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                if ($this->installHook($account)) {
                    $user = $this->createUser($account->getUsername(), $account->getPassword());
                    $account->setUser($user);

                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($user);
                    $em->persist($account);
                    $em->flush();

                    //TODO: Log user in

                    $this->get('session')->setFlash('success', 'You may now login with your existing cPanel creditentials.');

                } else {
                        $this->get('session')->setFlash('error', 'We could not verify that the account exists or that the password is correct.');
                }

                return new RedirectResponse($this->generateUrl('fos_user_security_login'));
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * Adds an account to the logged in user.
     *
     * @Route("/add", name="account_add")
     * @Template()
     */
    public function addAction()
    {
        $account = new Account();
        $servers = $this->getDoctrine()
            ->getRepository('HelioNetworksHelioPanelBundle:Server')
            ->findAll();
        $form = $this->createForm(new AccountType(), $account, array('servers' => $servers));

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                 if ($this->installHook($account)) {
                    $account->setUser($this->getUser());

                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($account);
                    $em->flush();

                    $this->get('session')->setFlash('success', 'The account was added successfully!');

                    return new RedirectResponse($this->generateUrl('heliopanel_index'));
                } else {
                    $this->get('session')->setFlash('error', 'We could not verify that the account exists or that the password is correct.');
                }
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/setActive", name="account_set_active")
     * @Method({"POST"})
     * @Template()
     */
    public function setActiveAction($_route)
    {
        $accountRequest = new SetActiveAccountRequest();
        $form = $this->createForm(new SetActiveAccountRequestType(), $accountRequest, array(
            'accounts' => $this->getUser()->getAccounts()->toArray(),
            'current_account' => $this->getActiveAccount(),
        ));

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST' && $_route == 'account_set_active') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $this->setActiveAccount($accountRequest->getActiveAccount());
            } else {
                $this->get('session')->setFlash('error', 'The active account was not updated');
            }

            if(!$url = $this->getRequest()->server->get('HTTP_REFERER')) {
                $url = $this->generateUrl('heliopanel_index');
            }

            return new RedirectResponse($url);
        }

        return array('form' => $form->createView());
    }

    //TODO: Add deleteAction
}
