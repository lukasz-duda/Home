select date(a.timestamp) as date,
       m.name,
       a.dose,
       a.unit
from medicine_application a
         join medicine m on a.medicine_id = m.id
where a.cat_id = ?
  and a.timestamp >= ?
  and a.timestamp < ?