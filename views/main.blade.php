<?php
    /** @var \App\Tasks[] $tasks */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>Test tasks</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</head>
<body>
<header class="mb-5">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        @if (!isAuth())
            <form class="form-inline my-2 my-lg-0" method="post" action="/login">
                <input class="form-control mr-sm-2" type="text" name="login" placeholder="Логин">
                <input class="form-control mr-sm-2" type="password" name="password" placeholder="Пароль">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Войти</button>
            </form>
        @else
            <a href="/logout" class="btn btn-primary">Выйти</a>
        @endif
    </nav>
</header>
<main>
    <section>
        <div class="container">
            @if($actionStatus === 'success')
                <div class="row mb-1">
                    <div class="col text-success">
                        Действие успешно выполнено
                    </div>
                </div>
            @elseif($actionStatus === 'error')
                <div class="row mb-1">
                    <div class="col text-danger">
                        Не верные данные
                    </div>
                </div>
            @endif

            <div class="row mb-5">
                <div class="col">
                    <form method="post" class="">
                        <div class="form-group">
                            <textarea name="text" required placeholder="Задача" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="username" required placeholder="Имя">
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="email" name="email" required placeholder="Email">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Добавить задачу">
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <h3 class="mb-3">Задачи</h3>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    Сортировать:
                    <a class="btn btn-link" href="{{ url(['order' => 'username']) }}">По имени ↓</a>
                    <a class="btn btn-link" href="{{ url(['order' => '-username']) }}">По имени ↑</a>
                    <a class="btn btn-link" href="{{ url(['order' => 'status']) }}">По статусу ↓</a>
                    <a class="btn btn-link" href="{{ url(['order' => '-status']) }}">По статусу ↑</a>
                    <a class="btn btn-link" href="{{ url(['order' => 'email']) }}">По email ↓</a>
                    <a class="btn btn-link" href="{{ url(['order' => '-email']) }}">По email ↑</a>
                </div>
            </div>
            @foreach($tasks as $task)
            <div class="card mb-3">
                <div class="card-body">
                    @if (isAuth())<form action="/edit?id={{ $task->id }}" method="POST">@endif
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <th style="width: 1px">Имя</th>
                                <td>{{ $task->username }}</td>
                            </tr>
                            <tr>
                                <th style="width: 1px">Email</th>
                                <td>{{ $task->email }}</td>
                            </tr>
                            <tr>
                                <th style="width: 1px">Задача</th>
                                <td>
                                    @if (isAuth())
                                        <textarea name="text" required placeholder="Задача" class="form-control">{{ $task->text }}</textarea>
                                    @else
                                        {!! nl2br(e($task->text)) !!}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 1px">Статус</th>
                                <td>{{ $task->status === \App\Tasks::STATUS_COMPLETED ? 'Выполнено' : 'Не выполнено' }}</td>
                            </tr>
                            </tbody>
                        </table>
                        @if(isAuth())
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" {{ $task->status === \App\Tasks::STATUS_COMPLETED ? 'checked' : '' }} name="complete">
                            <label class="form-check-label">Принять задачу</label>
                        </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Сохранить">
                            </div>
                        @endif
                        @if ($task->edited_by_admin)
                            <div class="text-center small text-muted">отредактировано админом</div>
                        @endif
                    @if(isAuth())
                    </form>
                    @endif
                </div>
            </div>
            @endforeach

            @if ($lastPage > 1)
            <div class="row">
                <nav style="margin: 0 auto">
                    <ul class="pagination">
                        @foreach(range(1, $lastPage) as $page)
                            <li class="page-item"><a class="page-link" href="{{ url(['page' => $page]) }}">{{ $page }}</a></li>
                        @endforeach
                    </ul>
                </nav>
            </div>
            @endif
        </div>
    </section>
</main>
</body>
</html>