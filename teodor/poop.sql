select date(p.timestamp)  as date,
       1                  as value,
       count(p.timestamp) as count
from poop p
where p.cat_id = ?
  and date(p.timestamp) >= ?
  and date(p.timestamp) <= ?
group by date(p.timestamp)