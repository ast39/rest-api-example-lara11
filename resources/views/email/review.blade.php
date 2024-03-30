@extends('email.layout')
@section('title')Новый отзыв о товаре@endsection
@section('content')
    <div style="text-transform: uppercase; font-size: 20px; padding: 15px 0; color: #4a4b48;" align="center">Добрый день!</div>
    <hr style="color: #333" />
    <p>Был оставлен отзыв о товаре на ресурсе «{{ env('APP_NAME') }}»</p>

    <table style="width: 100%; border-collapse: collapse; border-spacing: 0;">
        <tbody>
        <tr>
            <td style="width: 75px; vertical-align: baseline; color: #4a4b48;" valign="baseline"><b>Товар</b></td>
            <td style="padding-bottom: 15px; vertical-align: baseline;" valign="baseline">{{ $item }}</td>
        </tr>

        <tr>
            <td style="width: 75px; vertical-align: baseline; color: #4a4b48;" valign="baseline"><b>Автор</b></td>
            <td style="padding-bottom: 15px; vertical-align: baseline;" valign="baseline">{{ $user }}</td>
        </tr>

        <tr>
            <td style="width: 75px; vertical-align: baseline; color: #4a4b48;" valign="baseline"><b>Оценка</b></td>
            <td style="padding-bottom: 15px; vertical-align: baseline;" valign="baseline">{{ $rate }}</td>
        </tr>

        <tr>
            <td style="width: 75px; vertical-align: baseline; color: #4a4b48;" valign="baseline"><b>Отзыв</b></td>
            <td style="padding-bottom: 15px; vertical-align: baseline;" valign="baseline">{{ $body }}</td>
        </tr>
        </tbody>
    </table>

    <p style="text-align: center; color: #333;">Пожалуйста, не отвечайте на это письмо!</p>
@endsection
