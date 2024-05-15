Adds a block with an index to views.

Howto:

* Create a views field which classifies the current item into an index (e.g.
  first letter of the name). For performance reasons, only this field will be
  rendered internally, so it should not rely on other fields.

* Add a custom text to the header (or the footer) with the following code:
```{{ views_index_pager({ view: 'view_name', display: 'display', index_field: 'field_name_1' }) }}```
