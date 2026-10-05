"use client";

import { useEffect, useState } from "react";
import { usePathname } from "next/navigation";
import { Icon } from "./Icons";
import { fdaLine, phoneHref, posts, site, whatsappHref } from "@/lib/site";

const year = new Date().getFullYear();

export function Footer() {
  const path = usePathname() || "/";
  const phone = phoneHref();
  const whatsapp = whatsappHref("Hi, I want to order Elite Sleeper.");
  const hideBuy = path.startsWith("/order") || path.startsWith("/thank-you") || path.startsWith("/opt-out");
  const askHref = path === "/" ? "#contact" : "/#contact";
  const [showFab, setShowFab] = useState(false);

  useEffect(() => {
    if (hideBuy) {
      setShowFab(false);
      return;
    }
    const update = () => {
      const hero = document.querySelector(".home-hero .hero-actions");
      setShowFab(!hero || hero.getBoundingClientRect().bottom < 72);
    };
    update();
    window.addEventListener("scroll", update, { passive: true });
    window.addEventListener("resize", update);
    return () => {
      window.removeEventListener("scroll", update);
      window.removeEventListener("resize", update);
    };
  }, [hideBuy, path]);

  return (
    <>
      <footer className="site-footer">
        <div className="wrap footer-grid">
          <div>
            <p className="footer-name">{site.name}</p>
            <p className="footer-tag">{site.tagline}</p>
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
      {hideBuy || !showFab ? null : (
        <div className="fab-dock" aria-label="Quick actions">
          {phone && site.phoneLabel ? (
            <a className="fab fab-icon" href={phone}>
              <Icon name="phone" />
              <span className="sr-only">Call {site.phoneLabel}</span>
            </a>
          ) : null}
          {whatsapp ? (
            <a className="fab fab-icon" href={whatsapp}>
              <Icon name="chat" />
              <span className="sr-only">Order on WhatsApp</span>
            </a>
          ) : null}
          <a className="fab fab-ask" href={askHref}>
            <Icon name="mail" />
            Ask
          </a>
          <a className="fab fab-order" href="/order/">
            Order · {site.priceLabel}
          </a>
        </div>
      )}
    </>
  );
}
