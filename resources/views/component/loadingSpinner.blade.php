<style type="text/css">
    .loading {
        z-index: 1001;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .loading-content {
        background-size: cover;
        width: 60px;
        height: 60px;
        animation: spin 2s linear infinite;
        background-image: url('{{ asset('userfiles/image/logo/logo.png') }}');
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<section id="loading" class="loading" style="display: none;">
    <div id="loading-content" class="loading-content"></div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("loading").style.display = "flex";

        window.onload = function() {
            document.getElementById("loading").style.display = "none";
        };
    });

    $(document).ajaxStart(function() {
        $('#loading').css('display', 'flex');
    });

    $(document).ajaxStop(function() {
        $('#loading').css('display', 'none');
    });
</script>
