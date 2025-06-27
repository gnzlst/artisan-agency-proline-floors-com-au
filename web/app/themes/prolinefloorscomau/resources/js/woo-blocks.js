document.addEventListener('DOMContentLoaded', function () {
  var products = Array.from(
    document.querySelectorAll(
      '.wp-block-woocommerce-product-collection li.wc-block-product'
    )
  );
  var productData = [];
  var categoryCounts = {};
  var fetches = [];

  // First pass: collect product IDs and fetch categories
  products.forEach(function (product) {
    var imageLink = product.querySelector(
      '.wc-block-components-product-image a'
    );
    if (!imageLink || imageLink.querySelector('.proline-sample-button')) return;

    // Get product ID from data-wp-context or data-wp-key
    var productId = product.getAttribute('data-wp-context');
    if (productId) {
      try {
        productId = JSON.parse(productId).productId;
      } catch (e) {
        productId = null;
      }
    }
    if (!productId) {
      // fallback: try data-wp-key
      var key = product.getAttribute('data-wp-key');
      if (key && key.match(/product-item-(\d+)/)) {
        productId = key.match(/product-item-(\d+)/)[1];
      }
    }
    if (!productId) return;

    productData.push({ product, productId, imageLink });
    // Prepare fetch for categories
    fetches.push(
      fetch('/wp-json/proline/v1/product-categories/' + productId)
        .then(function (res) {
          return res.json();
        })
        .then(function (categories) {
          return { product, productId, imageLink, categories };
        })
    );
  });

  // After all categories are fetched
  Promise.all(fetches).then(function (results) {
    // Count category slugs
    results.forEach(function (item) {
      if (item.categories && item.categories.length) {
        item.categories.forEach(function (cat) {
          categoryCounts[cat.slug] = (categoryCounts[cat.slug] || 0) + 1;
        });
      }
    });
    // Find the most common category slug
    var mostCommonSlug = null;
    var maxCount = 0;
    for (var slug in categoryCounts) {
      if (categoryCounts[slug] > maxCount) {
        maxCount = categoryCounts[slug];
        mostCommonSlug = slug;
      }
    }
    // Second pass: update DOM for each product
    results.forEach(function (item) {
      // Create the button overlay
      var button = document.createElement('a');
      button.textContent = 'Add Sample to Cart';
      button.className =
        'proline-sample-button wp-block-button__link bg-proline-light text-proline-light block text-center';
      button.href = '#';
      button.addEventListener('click', function (e) {
        e.preventDefault();
        button.textContent = 'Adding...';
        button.disabled = true;
        var nonce =
          typeof wc_store_api_nonce !== 'undefined' ? wc_store_api_nonce : '';
        var headers = {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          'X-WP-Nonce': nonce,
        };
        fetch('/wp-json/wc/store/cart/add-item', {
          method: 'POST',
          headers: headers,
          body: JSON.stringify({
            id: parseInt(item.productId, 10),
            quantity: 1,
          }),
        })
          .then(function (res) {
            return res.json();
          })
          .then(function (data) {
            if (data && !data.code) {
              button.textContent = 'Sample added to cart!';
              button.classList.add('bg-green-500');
            } else {
              button.textContent =
                data && data.message ? data.message : 'Error';
              button.classList.add('bg-red-500');
              button.disabled = false;
            }
          })
          .catch(function (error) {
            button.textContent = 'Error';
            button.classList.add('bg-red-500');
            button.disabled = false;
          });
      });
      var wrapper = document.createElement('div');
      wrapper.className =
        'wp-block-button wc-block-components-product-button align-center proline-persimmon-button-woocommerce-add-sample-button';
      wrapper.appendChild(button);
      item.imageLink.appendChild(wrapper);

      // Insert categories above the product title, filtering out the most common
      var title = item.product.querySelector(
        '.wp-block-post-title, h3.wp-block-post-title'
      );
      if (title && item.categories && item.categories.length) {
        var filtered = item.categories.filter(function (cat) {
          return cat.slug !== mostCommonSlug;
        });
        if (filtered.length) {
          var catDiv = document.createElement('div');
          catDiv.className =
            'proline-product-categories mb-2 text-xs text-proline-dark';
          catDiv.textContent = filtered
            .map(function (cat) {
              return cat.name;
            })
            .join(' | ');
          title.parentNode.insertBefore(catDiv, title);
        }
      }
    });
  });
});
