<style>
    .home-popup .home-popup__background {
        width: 100%;
        height: 100%;
        top: 0px;
        left: 0px;
        position: fixed;
        background-color: rgba(0, 0, 0, 0.4);
        display: flex;
        -webkit-box-align: center;
        align-items: center;
        place-content: center;
        -webkit-box-pack: center;
        z-index: 9000;
    }

    .home-popup .home-popup__content {
        -webkit-box-flex: 0;
        flex: 0 1 auto;
        position: relative;
        width: 80%;
        max-width: 438px;
        max-height: 100%;
    }

    .home-popup .with-placeholder {
        background-position: center center;
        background-size: 60px 60px;
        background-repeat: no-repeat;
        background-image: url(data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 54 61' x='0' y='0' stroke='' fill='%23e5e4e4'%0A%3E%3Cpath d='M 99.2 59.9 H 86.7 c 0 -5.3 -2.7 -16.3 -11.7 -16.4 c -9.6 -.1 -11.8 11.9 -11.8 16.4 H 50.8 c -3.4 0 -2.7 3.4 -2.7 3.4 l 2.4 33 c 0 0 -.1 7.3 6.3 7.5 c .4 0 35.7 0 36.5 0 c 6.2 -.4 6.3 -7.5 6.3 -7.5 l 2.4 -33 C 102 63.2 102.5 59.8 99.2 59.9 z M 75.1 47.2 c 7.1 .2 7.9 11.7 7.7 12.6 H 67.1 C 67 58.9 67.5 47.4 75.1 47.2 z M 84.2 91.8 c -1 1.7 -2.7 3 -5 3.7 C 78 95.9 76.8 96 75.6 96 c -3.2 0 -6.5 -1.1 -9.3 -3.3 c -.8 -.6 -1 -1.5 -.5 -2.3 c .2 -.4 .7 -.7 1.2 -.8 c .4 -.1 .9 0 1.2 .3 c 3.2 2.4 8.3 4 11.9 1.6 c 1.4 -.9 2.1 -2.7 1.6 -4.3 c -.5 -1.6 -2.2 -2.7 -3.5 -3.4 c -1 -.6 -2.1 -1 -3.3 -1.4 c -.9 -.3 -1.9 -.7 -2.9 -1.2 c -2.4 -1.2 -4 -2.6 -4.8 -4.2 c -1.2 -2.3 -.6 -5.4 1.4 -7.5 c 3.6 -3.8 10 -3.2 14 -.4 c .9 .6 .9 1.7 .4 2.5 c -.5 .8 -1.4 .9 -2.2 .4 c -2 -1.4 -4.4 -2 -6.4 -1.7 c -2 .3 -4.7 2 -4.4 4.6 c .2 1.5 2 2.6 3.3 3.3 c .8 .4 1.5 .7 2.3 .9 c 4.3 1.3 7.2 3.3 8.6 5.7 C 85.4 86.9 85.4 89.7 84.2 91.8 z' transform='translate(-48 -43)' stroke='none' /%3E%3C/svg%3E);
    }

    .home-popup .simple-banner {
        border: 4px solid #fff;
        width: 100%;
        height: 100%;
        overflow: hidden;
        position: relative;
    }

    .home-popup a:-webkit-any-link {
        color: -webkit-link;
        cursor: pointer;
        text-decoration: underline;
    }

    .home-popup .banner-image {
        display: block;
        width: 100%;
        overflow: hidden;
    }

    .home-popup .home-popup__close-area {
        position: absolute;
        display: block;
        top: 0px;
        right: 0px;
        width: 15%;
        height: 19%;
        cursor: pointer;
    }

    .shopee-popup__close-btn {
        cursor: pointer;
        user-select: none;
        line-height: 40px;
        height: 30px;
        width: 30px;
        display: flex;
        -webkit-box-align: center;
        align-items: center;
        -webkit-box-pack: center;
        justify-content: center;
        position: absolute;
        box-sizing: border-box;
        background: rgb(239, 239, 239);
        top: -10px;
        right: -10px;
        border-radius: 20px;
        border: 3px solid rgb(239, 239, 239);
    }

    .home-popup__close-button {
        height: 16px;
        width: 16px;
        stroke: rgba(0, 0, 0, 0.5);
        stroke-width: 2px;
    }

    .home-popup svg:not(:root) {
        overflow-clip-margin: content-box;
        overflow: hidden;
    }

    .home-popup .shopee-popup__close-btn {
        cursor: pointer;
        user-select: none;
        line-height: 40px;
    }
</style>

@if (isset($slides['banner-popup']['item']) && count($slides['banner-popup']['item']))
    @php
        $popupItems = $slides['banner-popup']['item'] ?? [];
        $indexItem = rand(0, count($popupItems) - 1);
        $imagePopup = $popupItems[$indexItem]['image'];
    @endphp
    <div class="home-popup">
        <div class="home-popup__background">
            <div class="home-popup__content">
                <tcshop-banner-simple .type="popup">
                    <div class="simple-banner with-placeholder" style="">
                        <a target="_self" href="{{ route('home.index') }}"><img class="banner-image"
                                src="{{ $imagePopup }}" alt="Banner">
                        </a>
                    </div>
                </tcshop-banner-simple>
                <div class="home-popup__close-area">
                    <div class="shopee-popup__close-btn" role="button" tabindex="0" aria-label="Close">
                        <svg viewBox="0 0 16 16" stroke="#EE4D2D" class="home-popup__close-button">
                            <path stroke-linecap="round" d="M1.1,1.1L15.2,15.2"></path>
                            <path stroke-linecap="round" d="M15,1L0.9,15.1"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const popup = document.querySelector('.home-popup');
            const closeBtn = document.querySelector('.shopee-popup__close-btn');
            const background = document.querySelector('.home-popup__background');

            // Tắt popup
            function closePopup() {
                popup.style.display = 'none';
                document.body.style.overflow = 'auto'; // Bật lại cuộn chuột
            }

            // Sự kiện đóng popup khi nhấn nút "x"
            if (closeBtn) {
                closeBtn.addEventListener('click', closePopup);
            }

            // Sự kiện đóng popup khi nhấn ra ngoài nội dung
            if (background) {
                background.addEventListener('click', function(e) {
                    if (e.target === background) {
                        closePopup();
                    }
                });
            }

            // Vô hiệu hóa cuộn chuột khi popup mở
            if (popup) {
                document.body.style.overflow = 'hidden'; // Ngăn cuộn chuột
            }
        });
    </script>
@endif
