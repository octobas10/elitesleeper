"use client";

import { useEffect, useId, useRef, useState } from "react";
import { createPortal } from "react-dom";
import { usePathname } from "next/navigation";
import { Icon } from "./Icons";
import { nav, site } from "@/lib/site";

export function Header() {
  const path = usePathname() || "/";
  const onHome = path === "/";
  const menuId = useId();
  const toggleRef = useRef<HTMLButtonElement>(null);
  const panelRef = useRef<HTMLElement>(null);
  const [active, setActive] = useState("");
  const [open, setOpen] = useState(false);
  const [mounted, setMounted] = useState(false);

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

  useEffect(() => {
    setMounted(true);
  }, []);

  useEffect(() => {
    setOpen(false);
  }, [path]);

  useEffect(() => {
    const onResize = () => {
      if (window.innerWidth > 980) setOpen(false);
    };
    window.addEventListener("resize", onResize);
    return () => window.removeEventListener("resize", onResize);
  }, []);

  useEffect(() => {
    document.documentElement.classList.toggle("menu-open", open);
    if (!open) return;

    const panel = panelRef.current;
    const closeBtn = panel?.querySelector<HTMLElement>("[data-menu-close]");
    closeBtn?.focus();

    const onKey = (event: KeyboardEvent) => {
      if (event.key === "Escape") {
        setOpen(false);
        toggleRef.current?.focus();
        return;
      }
      if (event.key !== "Tab" || !panel) return;
      const items = panel.querySelectorAll<HTMLElement>("a[href], button:not([disabled])");
      if (!items.length) return;
      const first = items[0];
      const last = items[items.length - 1];
      if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
      } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
      }
    };

    window.addEventListener("keydown", onKey);
    return () => window.removeEventListener("keydown", onKey);
  }, [open]);

  function closeMenu() {
    setOpen(false);
  }

  const drawer = (
    <div className={`offcanvas${open ? " is-open" : ""}`}>
      <button type="button" className="offcanvas-backdrop" aria-label="Close menu" tabIndex={-1} onClick={closeMenu} />
      <nav
        id={menuId}
        ref={panelRef}
        className="offcanvas-panel"
        aria-label="Mobile"
        aria-hidden={open ? undefined : true}
        inert={open ? undefined : true}
      >
        <div className="offcanvas-head">
          <p>Menu</p>
          <button type="button" className="offcanvas-close" data-menu-close onClick={closeMenu}>
            <Icon name="close" />
            <span className="sr-only">Close menu</span>
          </button>
        </div>
        <div className="offcanvas-links">
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
        </div>
        <a className="btn" href="/order/" onClick={closeMenu}>
          Order · {site.priceLabel}
        </a>
        <p className="offcanvas-meta">
          <a href={`mailto:${site.email}`} onClick={closeMenu}>
            {site.email}
          </a>
          <span>
            Ships from {site.city}, {site.region}
          </span>
        </p>
      </nav>
    </div>
  );

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
        <button
          ref={toggleRef}
          type="button"
          className="menu-toggle"
          aria-expanded={open}
          aria-controls={menuId}
          onClick={() => setOpen((value) => !value)}
        >
          <Icon name="menu" />
          <span className="sr-only">Open menu</span>
        </button>
      </div>
      <p className="sr-only">{site.tagline}</p>
      {mounted ? createPortal(drawer, document.body) : null}
    </header>
  );
}
