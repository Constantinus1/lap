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
- database/migrations/2014_10_12_000000_create_users_table.php
- database/migrations/2014_10_12_100000_create_password_reset_tokens_table.php
- database/migrations/2019_08_19_000000_create_failed_jobs_table.php
- database/migrations/2019_12_14_000001_create_personal_access_tokens_table.php
- database/migrations/2025_10_27_094410_create_products_table.php
- database/migrations/2025_10_27_094550_create_addresses_table.php
- database/migrations/2025_10_27_094707_create_carts_table.php
- database/migrations/2025_10_27_094815_create_cart_items_table.php
- database/migrations/2025_10_27_094902_create_orders_table.php
- database/migrations/2025_10_27_094932_create_order_items_table.php
- database/migrations/2025_10_28_130924_make_user_id_and_address_id_nullable_in_orders_table.php
- database/migrations/2025_10_29_093605_add_indexes_to_tables.php
- database/migrations/2025_10_29_093643_add_stock_to_products_table.php
- database/migrations/2025_10_29_093719_add_is_available_to_products_table.php
- database/migrations/2025_10_29_094138_add_guest_fields_to_orders_table.php
- database/migrations/2025_10_29_141633_remove_payments_table_and_add_payment_method_to_orders.php
- database/migrations/2025_10_31_083756_create_sessions_table.php

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
- resources/js/pages/Addresses.vue

## Vue Components
- resources/js/components/AppHeader.vue
