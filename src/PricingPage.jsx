import React from 'react'
import { Button, Card, Section } from './components/pricing'
import { therapies, terms } from './data/pricingContent'

export default function PricingPage({
  bookingAction = 'popup',
  bookingUrl = '#',
  bookingPopupId,
  contactUrl = '#',
  natureUrl = 'https://claudiamaria.ro/shop/',
  useModuleStyling = false,
  whatsappGenericMessage = 'Bună, Claudia!\n\nAm văzut site-ul și aș dori să discut cu tine. Am o întrebare. Mă poți ajuta?',
  whatsappInitialMessage = 'Bună, Claudia!\n\nAm văzut pe site consultația inițială și aș dori să mă programez. Aș vrea să știu ce disponibilități ai.',
  whatsappLoyaltyMessage = 'Bună, Claudia!\n\nAm văzut programul de loialitate – „Pentru trupul tău. Pentru sănătatea ta.” și aș dori să aflu cum pot beneficia de el.',
  whatsappNumber = '',
  whatsappOnlineMessage = 'Bună, Claudia!\n\nAm văzut pe site consultația online și aș dori să discutăm despre o programare.',
}) {
  const normalizedWhatsappNumber = String(whatsappNumber).replace(/\D/g, '')
  const useWhatsapp = bookingAction === 'whatsapp' && Boolean(normalizedWhatsappNumber)
  const whatsappUrl = (message) => useWhatsapp ? `https://wa.me/${normalizedWhatsappNumber}?text=${encodeURIComponent(message)}` : ''
  const initialUrl = whatsappUrl(whatsappInitialMessage) || bookingUrl
  const onlineUrl = whatsappUrl(whatsappOnlineMessage) || bookingUrl
  const loyaltyUrl = whatsappUrl(whatsappLoyaltyMessage) || bookingUrl
  const genericUrl = whatsappUrl(whatsappGenericMessage)
  const appointmentPopupId = useWhatsapp ? undefined : bookingPopupId

  return <main className={`react-page cm-pricing-page${useModuleStyling ? ' cm-use-module-styling' : ''}`}>
    <section className="cm-hero"><div className="cm-container">
      <div className="cm-kicker">Investiția în tine</div><h1>Prețuri &amp; servicii</h1>
      <p>Nu trebuie să știi dinainte ce terapie ai nevoie. Prima întâlnire începe cu o consultație, iar apoi stabilim împreună direcția de lucru potrivită pentru tine.</p>
    </div></section>

    <Section kicker="01 · Consultații" title="Începem cu tine."><div className="cm-grid">
      <Card featured title="CONSULTAȚIE INIȚIALĂ + PRIMA ȘEDINȚĂ DE TERAPIE" price="690 RON" duration="90 min">
        <p>Prima întâlnire cu mine începe întotdeauna cu o consultație inițială. Discutăm despre ceea ce te aduce, istoricul tău și ceea ce simți, apoi evaluăm și începem lucrul terapeutic.</p>
        <p>La final stabilim împreună direcția de lucru și ceea ce este potrivit pentru tine în continuare.</p><p><strong>Nu trebuie să știi dinainte ce terapie ai nevoie.</strong></p>
        <div className="cm-card-cta"><Button href={initialUrl} popupId={appointmentPopupId}>PROGRAMEAZĂ CONSULTAȚIA INIȚIALĂ</Button></div>
      </Card>
      <Card title="CONSULTAȚIE ONLINE"><div className="cm-price">480 RON <small>· 60 min</small><br />600 RON <small>· 90 min</small></div>
        <p>Dacă nu ești din București sau din România, consultația online poate fi primul pas pentru a înțelege mai bine ceea ce se întâmplă cu tine și pentru a contura o direcție.</p>
        <p>Discutăm despre simptomele tale, istoricul și nevoile tale și, atunci când este posibil, te pot orienta către un terapeut potrivit din zona ta.</p><p>Poate fi, de asemenea, un punct de pornire pentru ghidare, continuitate și mentorat.</p>
        <div className="cm-card-cta"><Button href={onlineUrl} popupId={appointmentPopupId} secondary>SOLICITĂ O CONSULTAȚIE ONLINE</Button></div>
      </Card>
    </div></Section>

    <Section kicker="02 · Terapii" title="Nu alegi tu tehnica. O alegem împreună." intro="În practica mea, o ședință poate integra mai multe tehnici, în funcție de ceea ce are nevoie corpul tău. Detaliile fiecărei metode sunt prezentate pe paginile dedicate terapiilor.">
      <div className="cm-grid">{therapies.map(([title, price, duration, text, subtitle, note]) => <Card key={title} {...{ title, price, duration, text, subtitle, note }} />)}</div>
    </Section>

    <Section kicker="03 · Aromaterapie & ritualuri" title="Natură pentru sănătate."><div className="cm-grid">
      <Card title="RITUAL RAINDROP" price="690 RON" duration="75 min" text="Un ritual de relaxare și îngrijire corporală bazat pe tehnici manuale și utilizarea uleiurilor esențiale." />
      <Card title="AROMATERAPIE & ÎNGRIJIRE NATURALĂ" text="Natura face parte din filosofia mea de îngrijire a corpului și de susținere a stării de bine."><div className="cm-card-cta"><Button href={natureUrl} secondary>NATURĂ PENTRU SĂNĂTATE</Button></div></Card>
    </div></Section>

    <Section kicker="04 · Masaj personalizat" title="Lucrăm în funcție de ceea ce ai nevoie."><div className="cm-grid">
      <Card title="RELAXARE · TERAPEUTIC · DEEP TISSUE · AROMATERAPIE" price="350–1000 RON" duration="50–100 min" text="Ședința și durata sunt stabilite în funcție de tipul de masaj și de nevoile tale. Prețul este personalizat în funcție de durata și tipul de ședință." />
    </div></Section>

    <Section kicker="05 · Loialitate"><div className="cm-panel">
      <h2>LOIALITATE PENTRU CORPUL TĂU</h2><p className="cm-intro"><strong>Un angajament față de trupul tău, de sănătatea ta și față de prevenirea apariției sau agravării unor afecțiuni.</strong></p>
      <p>De multe ori, avem grijă de corpul nostru abia atunci când ceva începe să doară.</p><p>Dar sănătatea nu înseamnă doar să reparăm atunci când apare o problemă.</p>
      <p>Înseamnă și să prevenim, să ascultăm și să avem grijă de corpul nostru înainte ca el să ne oblige să ne oprim.</p><p>Programul de loialitate este pentru cei care aleg să își acorde în mod constant timp pentru sănătate, prevenție și echilibru.</p>
      <p><strong>Tu vii cu ceea ce simți, iar împreună alegem, de la o întâlnire la alta, ceea ce are nevoie corpul tău.</strong></p>
      <div className="cm-grid"><Card title="2 TERAPII / LUNĂ"><div className="cm-discount">20% REDUCERE</div><p>Aproximativ o ședință la fiecare două săptămâni.</p></Card><Card title="3 TERAPII / LUNĂ"><div className="cm-discount">30% REDUCERE</div><p>Aproximativ o ședință la fiecare zece zile.</p></Card></div>
      <p><strong>Plata se face în avans, iar programările se stabilesc în avans.</strong></p><p>Pacienții noi intră în program după prima întâlnire. Pacienții existenți pot alege programul direct.</p><div className="cm-card-cta"><Button href={loyaltyUrl} popupId={appointmentPopupId}>VREAU SĂ ALEG PROGRAMUL DE LOIALITATE</Button></div>
    </div></Section>

    <Section kicker="06 · Acces & sprijin">
      <div className="cm-panel"><h2>PENTRU PENSIONARI ȘI CAZURI SOCIALE</h2><div className="cm-discount">10–100% REDUCERE</div>
        <p>Pentru pensionari și pentru persoanele aflate în situații sociale dificile, pot acorda, în funcție de situația fiecăruia, reduceri între 10% și 100%.</p><p>Dacă ai nevoie de ajutor și simți că prețul unei terapii este prea mult pentru situația ta actuală, scrie-mi. Povestește-mi pe scurt situația ta, iar împreună putem vedea ce soluție este posibilă.</p><p><strong>Nu este nevoie să îți fie rușine să ceri ajutor.</strong></p>
        <div className="cm-card-cta"><Button href={genericUrl || contactUrl}>SOLICITĂ O DISCUȚIE</Button></div><p className="cm-note">Solicitarea poate fi făcută prin e-mail sau prin mesaj. Fiecare situație este analizată individual.</p>
      </div>
      <div className="cm-panel"><h2>PENTRU PACIENȚII ONCOLOGICI</h2><div className="cm-discount">50% REDUCERE</div><p><strong>Pentru pachete de minimum 3 ședințe, pe durata tratamentului oncologic.</strong></p>
        <p>Știu că un proces oncologic înseamnă mult mai mult decât tratamentul medical în sine. Înseamnă timp, oboseală, teamă, schimbări în corp și multe lucruri pe care uneori este greu să le duci singur.</p><p>Pentru pacienții aflați în tratament oncologic, vreau să fac terapia mea mai accesibilă în această perioadă. De aceea, ofer o reducere de 50% pentru pachetele de minimum 3 ședințe.</p><p>Dacă treci printr-un astfel de proces și simți că ai nevoie de sprijin, scrie-mi. Vom vedea împreună dacă și cum putem lucra, în funcție de situația ta și de recomandările medicale pe care le urmezi.</p>
        <div className="cm-card-cta"><Button href={genericUrl || contactUrl}>VORBEȘTE CU MINE</Button></div>
      </div>
    </Section>

    <section className="cm-terms"><div className="cm-container"><div className="cm-kicker">Informații importante</div><h2>Termeni &amp; condiții</h2>
      <ul>{terms.map((term) => <li key={term}>{term}</li>)}</ul><p className="cm-quote">Recomand să ajungi la ora programată, pentru a putea beneficia de relaxare și timpul tău de terapie.</p><p className="cm-quote"><strong>Învață să asculți de înțelepciunea trupului tău!</strong></p>
    </div></section>
    <section className="cm-final"><div className="cm-container"><h2>Nu trebuie să știi de unde să începi.</h2><p>Vii cu ceea ce simți.<br />De acolo începem, împreună.</p><Button href={genericUrl || bookingUrl} popupId={appointmentPopupId}>PROGRAMEAZĂ O CONSULTAȚIE</Button></div></section>
  </main>
}
