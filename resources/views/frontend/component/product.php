<?php 
    $product = [
        0 => [
            'title' => 'HP 22 All-in-One PC, Intel Pentium Silver J5040, 4GB RAM',
        ],
        1 => [
            'title' => 'Gateway 23.8" All-in-one Desktop, Fully Adjustable Stand'
        ],
        2 => [
            'title' => 'HP 24 All-in-One PC, Intel Core i3-1115G4, 4GB RAM'
        ],
        3 => [
            'title' => 'Dell Optiplex 9020 Small Form Business Desktop Tower PC'
        ]
    ];

    $countdown = [
        '2023-07-05 10:20:00',
        '2023-09-09 10:20:00',
        '2023-10-03 10:20:00',
        '2023-07-10 10:20:00',
    ];
?>
<div class="product-item product">
    <div class="percent">-17%</div>
    <a href="" class="image img-scaledown"><img src="resources/img/product-<?php echo rand(1, 4) ?>.png" alt=""></a>
    <div class="countdown uk-flex uk-flex-center" data-countdown="<?php echo $countdown[rand(0, 3)] ?>">
        <div class="uk-flex uk-flex-middle">
            <div class="countdown-item day">
                <div class="number">86</div>
                <div class="text">Day</div>
            </div>
            <div class="countdown-item hour">
                <div class="number">10</div>
                <div class="text">Hour</div>
            </div>
            <div class="countdown-item min">
                <div class="number">10</div>
                <div class="text">Min</div>
            </div>
            <div class="countdown-item sec">
                <div class="number">40</div>
                <div class="text">Sec</div>
            </div>
        </div>
    </div>
    <div class="info">
        <div class="brand">Apple</div>
        <div class="title"><a href="" title=""><?php echo $product[rand(0,3)]['title'] ?></a></div>
        <div class="rating">
            <div class="uk-flex uk-flex-middle">
                <div class="star">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                </div>
                <div class="rate-number">( <?php echo rand(0, 100) ?> )</div>
            </div>
        </div>
        <div class="price">
            <div class="uk-flex uk-flex-bottom">
                <div class="price-sale">9.000.000đ</div>
                <div class="price-old">10.900.000đ</div>
            </div>
        </div>
        <div class="progress-bar">
            <div class="progress-bar-normal"></div>
            <div class="progress-bar-active" style="width:<?php echo rand(10, 100) ?>%"></div>
            <div class="uk-flex uk-flex-middle uk-flex-space-between mt10">
                <span>Đang có: <strong><?php echo rand(100, 1000) ?></strong></span>
                <span>Đã bán: <strong><?php echo rand(100, 900) ?></strong></span>
            </div>
        </div>
        <div class="description">
            <p>27-inch (diagonal) Retina 5K display</p>
        </div>
        <div class="tools">
            <a href="" title=""><img src="resources/img/trend.svg" alt=""></a>
            <a href="" title=""><img src="resources/img/wishlist.svg" alt=""></a>
            <a href="" title=""><img src="resources/img/compare.svg" alt=""></a>
            <a href="#popup" data-uk-modal title=""><img src="resources/img/view.svg" alt=""></a>
        </div>
    </div>
</div>