# Laravel Webshop

Essential files for Laravel webshop with guest checkout, cart, and product management.

## Models
- app/Models/Product.php
- app/Models/Order.php
- app/Models/OrderItem.php
- app/Models/Cart.php
- app/Models/CartItem.php
- app/Models/Address.php
- app/Models/User.php

## Controllers
- app/Http/Controllers/ShopController.php
- app/Http/Controllers/ProductController.php
- app/Http/Controllers/CartController.php
- app/Http/Controllers/CheckoutController.php
- app/Http/Controllers/AddressController.php
- app/Http/Controllers/DashboardController.php

## Services
- app/Services/CartService.php
- app/Services/OrderService.php

## Middleware
- app/Http/Middleware/HandleInertiaRequests.php

## Routes
- routes/web.php

## Migrations
- database/migrations/0001_01_01_000000_create_users_table.php
- database/migrations/0001_01_01_000001_create_cache_table.php
- database/migrations/0001_01_01_000002_create_jobs_table.php
- database/migrations/2025_08_14_170933_add_two_factor_columns_to_users_table.php
- database/migrations/2025_10_27_083146_create_products_table.php
- database/migrations/2025_10_27_083157_create_orders_table.php
- database/migrations/2025_10_27_083209_create_order_items_table.php
- database/migrations/2025_10_27_083739_create_addresses_table.php
- database/migrations/2025_10_27_083752_create_carts_table.php
- database/migrations/2025_10_27_083754_create_cart_items_table.php

## Factories
- database/factories/ProductFactory.php
- database/factories/AddressFactory.php
- database/factories/OrderFactory.php
- database/factories/UserFactory.php

## Seeders
- database/seeders/DatabaseSeeder.php

## Vue Pages
- resources/js/pages/Shop.vue
- resources/js/pages/ProductDetail.vue
- resources/js/pages/Cart.vue
- resources/js/pages/Checkout.vue
- resources/js/pages/Invoice.vue
- resources/js/pages/Dashboard.vue

## Vue Components
- resources/js/components/AppHeader.vue
