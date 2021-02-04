select date(o.timestamp) as date,
       o.notes
from observation o
where o.cat_id = ?
  and date(o.timestamp) >= ?
  and date(o.timestamp) <= ?