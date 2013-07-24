@extends('error-emailer::layouts.common')

@section('title')
    @include('error-emailer::subject')
@stop

@section('emailbody')
    <table cellpadding="0" cellspacing="0" border="0" align="left" width="100%">
        <tr>
            <td valign="top">
                {{ $trace }}

                <h2>PHP environment:</h2>
                <pre style="max-width:600px;">
$_SERVER = {{ print_r($_SERVER, true) }}

$_REQUEST = {{ print_r($_REQUEST, true) }}
                </pre>
            </td>
        </tr>
    </table>
@stop