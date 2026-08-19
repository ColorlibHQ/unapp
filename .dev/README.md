# Pattern generator

`pgen.py` emits the theme's block patterns as PHP files. Every helper mirrors the
markup a core block's `save()` produces, so generated patterns validate in the
block editor.

```bash
python3 .dev/batch1.py     # heroes + logo cloud
python3 .dev/batch2.py     # social proof
python3 .dev/batch3.py     # features
python3 .dev/batch4.py     # pricing, FAQ, calls to action
python3 .dev/batch5.py     # company
python3 .dev/batch6.py     # content and blog
python3 .dev/batch7.py     # utility, alternative header/footer
python3 .dev/batch8.py     # full-page starters
```

Scripts write straight into `../patterns/`. `pgen.THEME` points at the theme root.
Hand-written patterns (`hero.php`, `services.php`, `footer.php`, the `hidden-*`
partials …) predate the generator and are maintained by hand — do not regenerate
them.

After changing anything: `php -l` each file, render every pattern through
`do_blocks()`, and parse them with `wp.blocks.parse` in the editor to catch
invalid block markup.
