<li <?php wc_product_class( '', $product ); ?>>
    <?php
    do_action( 'woocommerce_before_shop_loop_item' ); // opens <a>
    do_action( 'woocommerce_before_shop_loop_item_title' ); // outputs image

    // Add your custom button here, right after the image
    ?>
    <div class="w-full flex justify-center" style="margin-top: -8px;">
      <a href="#" class="block bg-proline-gray text-proline-dark px-4 py-2 hover:bg-white uppercase cursor-pointer text-center w-full" style="border-radius:0 0 8px 8px;">
        Add Sample to Cart
      </a>
    </div>
    <?php

    do_action( 'woocommerce_shop_loop_item_title' );
    do_action( 'woocommerce_after_shop_loop_item_title' );
    // do_action( 'woocommerce_after_shop_loop_item' ); // Remove this line to hide the default button
    ?>
</li>
