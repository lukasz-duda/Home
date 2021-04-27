select date(p.timestamp)  as date,
       1                  as value,
       count(p.timestamp) as count
from pee p
where p.cat_id = ?
  and p.timestamp >= ?
  and p.timestamp < ?
group by date(p.timestamp)