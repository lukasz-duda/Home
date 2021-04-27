select date(o.timestamp) as date,
       o.notes
from observation o
where o.cat_id = ?
  and o.timestamp >= ?
  and o.timestamp < ?