select date(m.timestamp) as date,
       m.dose            as value
from medicine_application m
where m.cat_id = ?
  and date(m.timestamp) >= ?
  and date(m.timestamp) <= ?
  and m.medicine_id = 6