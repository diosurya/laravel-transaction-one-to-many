<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Transaction One-to-Many

Transaksi dengan multiple Produk.


## Database

1. Table 'products'
- id (Primary Key)
- product_name
- price
- created_at
- updated_at

2. Table 'transactions'
- id (Primary Key)
- customer_name
- date
- total_price
- total_quantity
- transaction_number 
- created_at
- updated_at

3. Table 'transaction_details'
- id (Primary Key)
- transaction_id (Foreign Key ke transactions)
- product_id (Foreign Key ke products)
- quantity
- total_price
- created_at
- updated_at

## How To Run
- composer require laravel/ui
- npm install
- npm run dev
- npm run build
- php artisan serve

