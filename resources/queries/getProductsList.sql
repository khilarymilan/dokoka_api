SELECT
{!! COLUMNS |
  products.id AS product_id,
  products.name AS product_name,
  products.price AS product_price,
  products.details AS product_details,
  products.available AS product_available,

  categories.id AS category_id,
  categories.name AS category_name,

  branches.id AS branch_id,
  branches.name AS branch_name,
  branches.description AS branch_description,
  branches.address AS branch_address,
  branches.phone_number AS branch_address,

  stores.id AS store_id,
  stores.name AS store_name
!!}

FROM `products`

JOIN `categories`
ON products.category_id = categories.id

JOIN `branches`
ON branches.id = products.branch_id

JOIN `stores`
ON stores.id = branches.store_id
