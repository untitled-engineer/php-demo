@extends('layout')


@section('content')

<h1>Hello, {{ $name }}</h1>

<form>
    <fieldset>
        <legend>Settings</legend>
        <table style="width: 100%;">
            <tr>
                <td style="width: 222px;">
                    <label for="place-selector">
                        <select name="location" id="place-selector" >
                            @foreach ($places as $place)
                            <option value="{{ $place->getId() }}"> {{ $place->getName() }}</option>
                            @endforeach
                        </select>
                    </label>
                </td>
                <td style="width: 122px;">
                    <label for="range">Range (km)</label>
                    <input type="text" value="0" name="range" id="range"/>
                </td>
                <td>
                    <div id="distance-selector-wrapper">
                        <div id="slider"></div>
                    </div>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset>
        <legend>Nearest places</legend>
        <ol id="nearest-places" class="ajax"></ol>
    </fieldset>
</form>

@endsection
