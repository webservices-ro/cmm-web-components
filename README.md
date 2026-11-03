# Ateliere terapeutice — Claudia Maria

Modul WordPress/React pentru pagina permanentă **Ateliere terapeutice**. La activare:

- creează pagina publică `/ateliere-terapeutice/` cu shortcode-ul `[cmat_workshops]`;
- creează categoria WooCommerce `ateliere-terapeutice`;
- introduce legătura „Ateliere terapeutice” între „Filosofia mea” și „Blog” în meniul care conține aceste două elemente;
- afișează automat toate produsele publicate din categoria dedicată;
- păstrează pagina individuală WooCommerce, plata, stocul și recenziile proprii pentru fiecare atelier.

## Administrarea atelierelor

Pentru un atelier nou, creează un produs WooCommerce, completează titlul, descrierea scurtă, prețul, stocul și imaginea produsului, activează recenziile și bifează categoria **Ateliere terapeutice**. Atelierul apare automat în vitrină, fără modificarea paginii.

Coperta paginii se setează din **Imagine reprezentativă** pe pagina „Ateliere terapeutice”. Până la adăugarea imaginii finale, pagina folosește un fundal neutru. Shortcode-ul acceptă și atributele `hero_image` și `divider_image` dacă imaginile trebuie furnizate explicit prin URL.

## Dezvoltare

Identitatea și metadatele de release sunt definite în [`module.config.json`](./module.config.json).

## Scaffold a module

Create a new source repository from this template, clone it, then run:

```sh
npm run setup
npm install
npm run build
npm run package
git add .
git commit -m "Scaffold module"
npm run deploy
```

`npm run setup` asks for the plugin name, unique WordPress slug, unique PHP
function prefix, GitHub owner, and release repository. It updates the shared
configuration, package metadata, generated plugin entry file, and plugin
readme. The release repository defaults to the current GitHub owner and plugin
slug. Commit the generated configuration and source before deploying;
`npm run deploy` intentionally requires a clean working tree.

Each independently installed plugin must use its own slug, `Update URI`, and
PHP prefix. PHP functions share a global namespace across all plugins; never
reuse another plugin's prefix.

The starter shortcode is the PHP prefix in brackets (for example, `[mrm]`).
Replace `src/App.jsx` and `src/style.css` with the module UI.

## Releases

By default, the release workflow publishes the versioned ZIP, stable
`<slug>.zip`, and `update.json` as GitHub Release assets in the configured
release repository. Configure the release repository to match the source
repository to use the workflow's built-in `GITHUB_TOKEN` without another
secret.

If publishing to a separate repository, add a `RELEASE_REPO_TOKEN` Actions
secret to the source repository. It needs permission to create releases and
upload assets in the release repository. That repository must have a `main`
branch.

The generated manifest URL is stable:

`https://github.com/<owner>/<release-repository>/releases/latest/download/update.json`

Make the source repository a GitHub template repository from its repository
Settings page (**General → Template repository**) before distributing it.
# wordpress-react-module-template
