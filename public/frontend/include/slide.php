<div class="panel-slide page-setup">
    <div class="uk-container uk-container-center">
        <div class="swiper-container">
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-wrapper">
                <?php for($i = 1; $i <= 2; $i++){  ?>
                <div class="swiper-slide">
                    <div class="slide-item">
                        <div class="slide-overlay">
                            <div class="slide-title">Rau củ tươi <br> Khuyến mãi khủng</div>
                            <div class="slide-description">Tiết kiệm đến 50% cho đơn hàng đầu tiên </div>
                        </div>
                        <div class="subcribe-form">
                            <form action="" class="uk-form form">
                                <input type="text" name="email" value="" class="input-text" placeholder="Nhập vào Email Của bạn">
                                <button type="submit" name="submit" class="btn-send">Subcribe</button>
                            </form>
                        </div>
                        <span class="image"><img src="resources/img/slide-<?php echo $i; ?>.png" alt=""></span>
                    </div>
                    
                </div>
                <?php }  ?>
                
            </div>
            <div class="swiper-pagination"></div>
        </div>
        
    </div>
</div>