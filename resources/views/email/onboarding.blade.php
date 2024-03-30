@extends('email.layout')
@section('title')Новый пользователь в системе@endsection
@section('content')
    <div style="text-transform: uppercase; font-size: 20px; padding: 15px 0; color: #4a4b48;" align="center">Добрый день!</div>
    <hr style="color: #333" />
    <p>Была создана учетная запись на ресурсе «{{ env('APP_NAME') }}»</p>

    <table style="width: 100%; border-collapse: collapse; border-spacing: 0;">
        <tbody>
        <tr>
            <td style="width: 75px; vertical-align: baseline; color: #4a4b48;" valign="baseline"><b>Имя</b></td>
            <td style="padding-bottom: 15px; vertical-align: baseline;" valign="baseline">{{ $name }}</td>
        </tr>

        <tr>
            <td style="width: 75px; vertical-align: baseline; color: #4a4b48;" valign="baseline"><b>Логин</b></td>
            <td style="padding-bottom: 15px; vertical-align: baseline;" valign="baseline">{{ $email }}</td>
        </tr>

        <tr>
            <td style="width: 75px; vertical-align: baseline; color: #4a4b48;" valign="baseline"><b>Пароль</b></td>
            <td style="padding-bottom: 15px; vertical-align: baseline;" valign="baseline">{{ $password }}</td>
        </tr>

        <tr>
            <td style="width: 75px; vertical-align: baseline; color: #4a4b48;" valign="baseline"><b>Адрес API</b></td>
            <td style="padding-bottom: 15px; vertical-align: baseline;" valign="baseline">{{ env('APP_URL') }}</td>
        </tr>

        <tr>
            <td style="width: 75px; vertical-align: baseline; color: #4a4b48;" valign="baseline"><b>Адрес API</b></td>
            <td style="padding-bottom: 15px; vertical-align: baseline;" valign="baseline">{{ env('APP_URL') . DIRECTORY_SEPARATOR . env('APP_SWAGGER_DOCS') }}</td>
        </tr>
        </tbody>
    </table>

    <p style="text-align: center; color: #333;">Пожалуйста, не отвечайте на это письмо!</p>
@endsection
