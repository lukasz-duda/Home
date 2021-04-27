select meals.start_date                                                as date,
       round(sum(meals.meal_weight / meals.daily_demand_weight * 100)) as value
from (
         select date(m.start)                 as start_date,
                m.start_weight - m.end_weight as meal_weight,
                (
                    select d.weight
                    from daily_demand d
                    where d.cat_id = m.cat_id
                      and d.food_id = m.food_id
                      and date(d.timestamp) <= date(m.start)
                    order by d.timestamp desc
                    limit 1
                )                             as daily_demand_weight
         from meal m
         where m.cat_id = ?
           and m.start >= ?
           and m.start < ?
     ) meals
group by start_date;