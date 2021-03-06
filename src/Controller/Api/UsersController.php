<?php
namespace Api\Controller\Api;

use Api\Controller\ApiControllerTrait;
use Api\Controller\AppController;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use \Firebase\JWT\JWT;

/**
 * Users Controller
 *
 */
class UsersController extends AppController
{
    use ApiControllerTrait;

    public function initialize()
    {
        parent::initialize();
        
        $this->Auth->allow('token');
        $this->loadComponent('Api.ApiBuilder');
    }

    public function token()
    {
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException('Invalid username or password');
        }

        $this->set('data', [
            'user' => $user,
            'token' => $token = JWT::encode([
                'id' => $user['id'],
                'user' => $user,
                'exp' => time() + 604800
            ],
                Security::salt())
        ]);

        $this->ApiBuilder->execute();
    }

    public function me()
    {
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException('Invalid username or password');
        }

        $this->set('data', $user);

        $this->ApiBuilder->execute();
    }

}
