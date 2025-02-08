@if (isset($data))
    <script>
        window.filamentData = @js($data)
    </script>
@endif

@foreach ($assets as $asset)
    @if (! $asset->isLoadedOnRequest())
        {{ $asset->getHtml() }}
    @endif
@endforeach
<script>
    window.PassOn = function (message){
        window.parent.postMessage(message, "*")
    }

    window.onload = function () {
        var body = document.body,
            html = document.documentElement;

        var height = Math.max( body.scrollHeight, body.offsetHeight,
                            html.clientHeight, html.scrollHeight, html.offsetHeight );
        window.PassOn({
            source: "console-event",
            payload: {
                type: "height",
                value: height + 100

            }
        })


        let elements = window.document.querySelectorAll("a");

        for ( let i = 0; i < elements.length; i++){
            elements[i].addEventListener("click", function(e) {
                console.log("PointerEvent: ",e.target)
                let url = '';
                if(e.target.tagName != 'A'){
                    if(e.target.parent.tagName == 'A'){
                        url = e.target.parent.href
                    }
                }else{
                    url = e.target.href
                }
                e.preventDefault();
                if (e.stopPropagation) e.stopPropagation();
                window.PassOn({
                    source: "console-event",
                    payload: {
                        type: "url-update",
                        value: url

                    }
                })
            })
        }

    }

</script>
<style>
    :root {
        @foreach ($cssVariables ?? [] as $cssVariableName => $cssVariableValue) --{{ $cssVariableName }}:{{ $cssVariableValue }}; @endforeach
    }
    .fi-topbar{
        display: none;
    }
    .fi-sidebar{
        display: none;
    }
    .fi-main{
        min-height: 100vh;
        min-width: 100%;
    }
</style>
