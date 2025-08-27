<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} - Authorization</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .header {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            text-align: center;
            color: #0077cc;
        }

        .user {
            text-align: center;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .description {
            margin-bottom: 1rem;
            text-align: center;
        }

        .scopes {
            margin-top: 1rem;
            margin-bottom: 1.5rem;
        }

        .scopes p {
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .scopes ul {
            padding-left: 1.5rem;
            margin: 0;
        }

        .scopes li {
            margin-bottom: 0.5rem;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-approve {
            background-color: #28a745;
            color: #fff;
        }

        .btn-approve:hover {
            background-color: #218838;
        }

        .btn-cancel {
            background-color: #dc3545;
            color: #fff;
        }

        .btn-cancel:hover {
            background-color: #c82333;
        }

        form {
            display: inline;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">Authorization Request</div>

        <div class="user">
            @php use Illuminate\Support\Facades\Auth; @endphp
            @if(Auth::check())
                Hola, {{ Auth::user()->name }}
            @else
                No hay usuario autenticado
            @endif
        </div>

        <div class="description">
            <strong>{{ $client->name }}</strong> solicita acceso a tu cuenta.
        </div>

        @if (count($scopes) > 0)
        <div class="scopes">
            <p>Esta aplicación podrá:</p>
            <ul>
                @foreach ($scopes as $scope)
                    <li>{{ $scope->description }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="buttons">
            <form method="post" action="{{ config('oauth.approve_url') }}">
                @csrf
                <input type="hidden" name="state" value="{{ $request->state }}">
                <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                <button type="submit" class="btn btn-approve">Autorizar</button>
            </form>

            <form method="post" action="{{ config('oauth.deny_url') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="state" value="{{ $request->state }}">
                <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                <button class="btn btn-cancel">Cancelar</button>
            </form>
        </div>
    </div>
</body>

</html>