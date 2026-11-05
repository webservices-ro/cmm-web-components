# Ateliere terapeutice — Claudia Maria

Modul WordPress/React pentru pagina permanentă **Ateliere terapeutice**. La activare:

- creează pagina publică `/ateliere-terapeutice/` cu shortcode-ul `[cmat_workshops]`;
- înregistrează tipul de conținut **Ateliere** în WordPress;
- introduce legătura „Ateliere terapeutice” între „Filosofia mea” și „Blog” în meniul care conține aceste două elemente;
- afișează automat toate atelierele publicate;
- păstrează produsele WooCommerce complet separate de prezentarea atelierelor.

## Administrarea atelierelor

Pentru un atelier nou, deschide **Ateliere → Adaugă atelier** în administrarea WordPress. Completează titlul, rezumatul, conținutul și imaginea reprezentativă, apoi publică. Atelierul apare automat în pagina „Ateliere terapeutice”, fără să fie legat de un produs WooCommerce.

Conținutul fiecărui atelier este disponibil la adresa `/atelier/numele-atelierului/`. Poți folosi shortcode-uri de pagină, inclusiv `[cmm_component page="daruri-sacre-2026"]`, direct în conținutul atelierului.

Coperta paginii se setează din **Imagine reprezentativă** pe pagina „Ateliere terapeutice”. Până la adăugarea imaginii finale, pagina folosește un fundal neutru. Shortcode-ul acceptă și atributele `hero_image` și `divider_image` dacă imaginile trebuie furnizate explicit prin URL.

## Pagini individuale

Pagina **Daruri Sacre – Ediția 2026** poate fi inclusă într-o pagină, într-o postare sau într-un custom post type cu shortcode-ul:

```text
[cmm_component page="daruri-sacre-2026"]
```

Pentru a lega butonul de rezervare la o pagină sau la un produs, folosește atributul `reservation_url`:

```text
[cmm_component page="daruri-sacre-2026" reservation_url="https://example.com/rezervare/"]
```

Implicit, butonul „Rezervă-ți locul” adaugă în coș produsul WooCommerce cu ID-ul `2047`. ID-ul poate fi schimbat direct în shortcode dacă este necesar:

```text
[cmm_component page="daruri-sacre-2026" product_id="2047"]
```

Descrierea compactă Daruri Sacre poate fi introdusă în descrierea produsului WooCommerce astfel:

```text
[cmm_component page="daruri-sacre-product-description" presentation_url="https://example.com/atelier/daruri-sacre-2026/"]
```

Când comanda pentru produsul WooCommerce cu ID-ul `2047` ajunge la statusul `Completed`, clientul primește automat e-mailul personalizat Daruri Sacre. Mesajul nu este trimis la confirmarea plății sau cât timp comanda are statusul `Processing`. Pentru aceste comenzi, e-mailul generic WooCommerce „Comandă finalizată” este dezactivat, astfel încât clientul să primească un singur mesaj. E-mailul personalizat folosește designul curent al e-mailurilor WooCommerce, prenumele de facturare și valoarea achitată din comandă. Fiecare tranziție nouă la statusul `Completed` declanșează mesajul, iar rezultatul trimiterii este înregistrat în notele comenzii.

Shortcode-ul existent `[cmat_workshops]` continuă să afișeze pagina atelierelor terapeutice.

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
