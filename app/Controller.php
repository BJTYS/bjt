<?php

declare(strict_types=1);

namespace App;

use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class Controller
{
    public function main(ServerRequest $request): Response
    {
        $params = $request->getQueryParams();
        $page = isset($params['page']) ? (int) $params['page'] : 1;
        $order = isset($params['order']) ? [
            'by'        => trim($params['order'], '-'),
            'direction' => substr($params['order'], 0, 1) === '-' ? 'desc' : 'asc',
        ] : [
            'by'        => 'name',
            'direction' => 'asc',
        ];

        $perPage = 3;
        $count = Tasks::count();
        $tasks = Tasks::take($perPage)->skip(($page - 1) * $perPage)->orderBy($order['by'], $order['direction'])->get();

        return view('main', [
            'tasks'        => $tasks,
            'page'         => $page,
            'lastPage'     => (int) ceil($count / $perPage),
            'actionStatus' => getActionStatus(),
        ]);
    }

    public function addTask(ServerRequest $request): Response
    {
        if (ValidateTask::validate($request)) {
            Tasks::createFromDTO(TaskAddDTO::fromRequest($request));
            setActionStatus('success');
        } else {
            setActionStatus('error');
        }
        return new Response\RedirectResponse('/', 302);
    }

    public function editTask(ServerRequest $request): Response
    {
        if (!isAuth()) {
            setActionStatus('error');
            return new Response\RedirectResponse('/', 302);
        }

        $task = Tasks::where('id', $request->getQueryParams()['id'])->first();
        $task->updateFromDTO(TaskEditDTO::fromRequest($request));

        setActionStatus('success');
        return new Response\RedirectResponse('/', 302);
    }

    public function login(ServerRequest $request)
    {
        $input = $request->getParsedBody();
        if ($input['login'] === 'admin' && $input['password'] === '123') {
            $_SESSION['isLogged'] = true;
        } else {
           setActionStatus('error');
        }

        return new Response\RedirectResponse('/');
    }

    public function logout()
    {
        session_destroy();
        return new Response\RedirectResponse('/');
    }
}