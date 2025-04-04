SELECT * FROM products 
LEFT JOIN price_list_items ON products.id = price_list_items.product_id
LEFT JOIN 
( 
        select * from  price_lists
     order by priority asc
     limit 1
     )
     price_lists ON price_list_items.price_list_id = price_lists.id AND start_date <= now() AND end_date >= now()
JOIN country_currencies ON price_lists.country_currency_id = country_currencies.id
JOIN countries ON countries.id = country_currencies.country_id AND countries.code = 'EG'
JOIN currencies ON currencies.id = country_currencies.currency_id AND currencies.code = 'EGP'
-- WHERE price_list_items.price_list_id = 1