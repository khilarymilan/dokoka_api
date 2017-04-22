<?php
$qryDistance =
  "12734890 *
    ASIN(SQRT(
      POW(SIN((RADIANS(branches.latitude) - RADIANS( {{ FROM_LAT }} )) / 2), 2) +
      COS(RADIANS( {{ FROM_LAT }} )) *
      COS(RADIANS(branches.latitude)) *
      POW(SIN((RADIANS(branches.longitude) - RADIANS( {{ FROM_LNG }} )) / 2), 2)
    ))";
?>
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
  branches.name AS `branch_name`,
  branches.description AS branch_description,
  branches.address AS branch_address,
  branches.phone_number AS branch_phone_number,

  stores.id AS store_id,
  stores.name AS store_name,

  <?php echo @$LATLNG ? $qryDistance : 'NULL' ?> AS `distance`
!!}

FROM `products`

JOIN `categories`
ON products.category_id = categories.id

JOIN `branches`
ON branches.id = products.branch_id

JOIN `stores`
ON stores.id = branches.store_id

WHERE 1 = 1

  AND products.available >= 1

<?php if (@$LATLNG && !@$PRODUCT_ID) { ?>
  AND <?php echo $qryDistance ?> <= 1000
<?php } ?>

<?php if(@$CATEGORY_ID) { ?>
  AND categories.id = {{ CATEGORY_ID }}
<?php } ?>

<?php if(@$SEARCH_KEYWORDS) { ?>
  AND CONCAT(products.name, ' ', products.details) REGEXP ('(<?php echo implode(")|(", preg_split('/[\s\|\/]+/', $SEARCH_KEYWORDS)) ?>)')
<?php } ?>

<?php if(@$PRODUCT_ID) { ?>
  AND products.id = {{ PRODUCT_ID }}
<?php } ?>

<?php if(@$PRICE_MIN) { ?>
  AND products.price >= {{ PRICE_MIN }}
<?php } ?>

<?php if(@$PRICE_MAX) { ?>
  AND products.price <= {{ PRICE_MAX }}
<?php } ?>
