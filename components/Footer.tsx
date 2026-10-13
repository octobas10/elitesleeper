"use client";

import { Fab } from "./Fab";
import { fdaLine, phoneHref, posts, site, whatsappHref } from "@/lib/site";

const year = new Date().getFullYear();

export function Footer() {
  const phone = phoneHref();
  const whatsapp = whatsappHref("Hi, I want to order Elite Sleeper.");

  return (
    <>
      <footer className="site-footer">
        <div className="wrap footer-grid">
          <div>
            <a className="footer-brand" href="/">
              <span className="brand-name">{site.name}</span>
              <span className="brand-tag">{site.tagline}</span>
            </a>
            <p>
              {site.street}
              <br />
              {site.city}, {site.region} {site.postal}
              <br />
              {site.country}
            </p>
            <p>
              <a href={`mailto:${site.email}`}>{site.email}</a>
            </p>
            {phone && site.phoneLabel ? (
              <p>
                <a href={phone}>{site.phoneLabel}</a>
              </p>
            ) : null}
            {whatsapp ? (
              <p>
                <a href={whatsapp}>Order on WhatsApp</a>
              </p>
            ) : null}
          </div>
          <div>
            <p className="footer-label">Shop</p>
            <a href="/#bottle">The bottle</a>
            <a href="/order/">Order</a>
            <a href="/#ingredients">Ingredients</a>
            <a href="/#how">How it works</a>
            <a href="/#reviews">Reviews</a>
            <a href="/#faq">FAQs</a>
          </div>
          <div>
            <p className="footer-label">Blog</p>
            <a href="/#blog">From the desk</a>
            {posts.map((post) => (
              <a key={post.slug} href={`/blog/${post.slug}/`}>
                {post.label}
              </a>
            ))}
          </div>
          <div>
            <p className="footer-label">Desk</p>
            <a href="/#contact">Contact</a>
            <a href="/opt-out/">Opt out</a>
            <a href="/cancel-plan/">Cancel monthly</a>
            <a href="/shipping/">Shipping</a>
            <a href="/refund-policy/">Refunds</a>
          </div>
          <div>
            <p className="footer-label">Policies</p>
            <a href="/privacy/">Privacy</a>
            <a href="/terms/">Terms</a>
            <p>Visa, Mastercard, American Express, and Discover.</p>
          </div>
        </div>
        <div className="wrap footer-base">
          <p className="fine">{fdaLine}</p>
          <p>
            Copyright {site.yearStart}-{year} {site.name}. All rights reserved.
          </p>
        </div>
      </footer>
      <Fab />
    </>
  );
}
