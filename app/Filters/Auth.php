<?php

namespace App\Filters;

use App\Libraries\Visitor;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Auth implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        //
        $log = [];
        helper(['Permission_helper']);
        $response = service('response');
        if (empty(session('user'))) {
            if (isset($arguments[0]) && $arguments[0] == 'Y') {
                return redirect()->to('/login')->with('messageLogin', '<div class="alert alert-danger">Anda Tidak Mempunyai Akses</div>');
            } else {
                $log['errorCode'] = 2;
                $log['errorMessage'] = 'Anda Belum Login';
                return $response->setJSON($log);
            }
        } else {
            if (isset($arguments[1]) && isset($arguments[2])) {
                if (enforce($arguments[1], $arguments[2]) == false) {
                    if (isset($arguments[0]) && $arguments[0] == 'Y') {
                        return redirect()->to('/login')->with('messageLogin', '<div class="alert alert-danger">Anda Tidak Mempunyai Akses</div>');
                    } else {
                        $log['errorCode'] = 2;
                        $log['errorMessage'] = 'Anda Belum Login';
                        return $response->setJSON($log);
                    }
                } 
            }

            if (isset($arguments[0]) && $arguments[0] == 'Y') {
                if (env('CI_ENVIRONMENT') == 'production') {
                    $visitor = new Visitor();
                    $visitor->visit();
                    // uncomment to get visitor details
                    // $visitor->visitDetail();
                }
            }
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
