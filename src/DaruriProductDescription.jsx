export default function DaruriProductDescription({ config = {} }) {
  return (
    <section className="daruri-product" aria-labelledby="daruri-product-title">
      <h2 id="daruri-product-title">Atelier de parfumerie botanică și reconectare senzorială</h2>
      <p className="daruri-product__meta">12 decembrie 2026 · București · 10:00–15:00</p>

      <p className="daruri-product__lead">
        De cele mai multe ori, cel mai frumos dar pe care ni-l putem oferi este timpul în care ne oprim,
        respirăm, simțim și ne reconectăm cu trupul nostru, cu oamenii, cu noi înșine, cu natura și cu ceea
        ce contează.
      </p>

      <p>
        Te invit într-o experiență în care plantele aromatice, esențele naturale, rășinile, ceaiurile și
        bucuria creației se întâlnesc într-un spațiu dedicat prezenței, simțurilor și descoperirii de sine.
      </p>

      <p>
        Este un prilej de a descoperi lumea fascinantă a plantelor aromatice, de a înțelege caracterul și
        povestea lor și de a transforma această întâlnire într-o experiență personală.
      </p>

      <p className="daruri-product__closing">
        Iar la final, vei pleca nu doar cu propriul parfum botanic și produse botanice artizanale, ci și cu
        amintirea unui timp oferit ție.
      </p>

      <div className="daruri-product__action">
        <a href={config.presentationUrl || '#daruri-sacre'}>Descoperă atelierul</a>
      </div>
    </section>
  )
}
