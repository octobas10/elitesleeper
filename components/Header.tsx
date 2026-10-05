"use client";

import { useEffect, useRef, useState } from "react";
import { usePathname } from "next/navigation";
import { Icon } from "./Icons";
import { nav, site } from "@/lib/site";

export function Header() {
  const path = usePathname() || "/";
  const onHome = path === "/";
  const menuRef = useRef<HTMLDetailsElement>(null);
  const [active, setActive] = useState("");

  useEffect(() => {
    if (!onHome) {
      setActive("");
      return;
    }

    const ids = nav.map((item) => item.id);
    const mark = () => {
      const line = 140;
      let current = "";
      for (const id of ids) {
        const el = document.getElementById(id);
        if (!el) continue;
        if (el.getBoundingClientRect().top <= line) current = id;
      }
      setActive(current);
    };

    mark();
    window.addEventListener("scroll", mark, { passive: true });
    window.addEventListener("hashchange", mark);
    return () => {
      window.removeEventListener("scroll", mark);
      window.removeEventListener("hashchange", mark);
    };
  }, [onHome]);

  function closeMenu() {
    if (menuRef.current) menuRef.current.open = false;
  }

  return (
    <header className="site-header">
      <div className="util-bar">
        <div className="wrap util-row">
          <p>Ships from {site.city}, {site.region}</p>
          <p>
            <a href={`mailto:${site.email}`}>{site.email}</a>
          </p>
        </div>
      </div>
      <div className="wrap header-row">
        <a className="brand" href="/">
          <img
            src="/images/logo.png"
            alt="Elite Sleeper. We Simplify Sleeping."
            width={343}
            height={131}
          />
        </a>
        <nav className="desk-nav" aria-label="Primary">
          {nav.map((item) => (
            <a
              key={item.id}
              href={onHome ? `#${item.id}` : item.href}
              aria-current={onHome && active === item.id ? "location" : undefined}
              onClick={() => setActive(item.id)}
            >
              {item.label}
            </a>
          ))}
          <a className="btn btn-small btn-order" href="/order/">
            Order · {site.priceLabel}
          </a>
        </nav>
        <details className="mobile-nav" ref={menuRef}>
          <summary>
            <Icon name="menu" />
            <span>Menu</span>
          </summary>
          <nav aria-label="Mobile">
            {nav.map((item) => (
              <a
                key={item.id}
                href={onHome ? `#${item.id}` : item.href}
                aria-current={onHome && active === item.id ? "location" : undefined}
                onClick={() => {
                  setActive(item.id);
                  closeMenu();
                }}
              >
                {item.label}
              </a>
            ))}
            <a className="btn" href="/order/" onClick={closeMenu}>
              Order · {site.priceLabel}
            </a>
          </nav>
        </details>
      </div>
      <p className="sr-only">{site.tagline}</p>
    </header>
  );
}
