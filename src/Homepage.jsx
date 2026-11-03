import React from 'react'
import {
  About,
  Audience,
  CentralIdea,
  Courses,
  FinalCallToAction,
  Hero,
  IntegrativeMedicine,
  Process,
  Resources,
  StudentTestimonials,
  Testimonials,
  Therapies,
} from './components/home/sections'

export default function Homepage({
  bookingUrl = '#contact',
  bookingPopupId,
  aboutImageUrl,
  coursesImageUrl,
  coursesUrl = '#',
  heroImageUrl,
  manualImageUrl,
  natureUrl = 'https://claudiamaria.ro/shop/',
  raindropImageUrl,
  reflexologyImageUrl,
  ritualImageUrl,
  therapiesUrl = '/servicii/',
}) {
  return <div className="cm-home">
    <main id="top">
      <Hero bookingUrl={bookingUrl} bookingPopupId={bookingPopupId} imageUrl={heroImageUrl} />
      <CentralIdea />
      <About imageUrl={aboutImageUrl} />
      <Audience />
      <Therapies therapiesUrl={therapiesUrl} natureUrl={natureUrl} manualImageUrl={manualImageUrl} reflexologyImageUrl={reflexologyImageUrl} raindropImageUrl={raindropImageUrl} ritualImageUrl={ritualImageUrl} />
      <Process />
      <IntegrativeMedicine />
      <Testimonials />
      <Resources />
      <Courses coursesUrl={coursesUrl} imageUrl={coursesImageUrl} />
      <StudentTestimonials />
      <FinalCallToAction bookingUrl={bookingUrl} bookingPopupId={bookingPopupId} />
    </main>
  </div>
}
