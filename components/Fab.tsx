"use client";

import { useEffect, useId, useRef, useState } from "react";
import { usePathname } from "next/navigation";
import { Icon } from "./Icons";
import { site } from "@/lib/site";

export function Fab() {
  const path = usePathname() || "/";
  const hideOnOrder = path.startsWith("/order");
  const askHref = path === "/" ? "#contact" : "/#contact";
  const [visible, setVisible] = useState(false);
  const [open, setOpen] = useState(false);
  const rootRef = useRef<HTMLDivElement>(null);
  const menuId = useId();

  useEffect(() => {
    setOpen(false);
  }, [path]);

  useEffect(() => {
    if (hideOnOrder) {
      setVisible(false);
      setOpen(false);
      return;
    }
    const update = () => {
      const hero = document.querySelector(".home-hero .hero-actions");
      setVisible(!hero || hero.getBoundingClientRect().bottom < 72);
    };
    update();
    window.addEventListener("scroll", update, { passive: true });
    window.addEventListener("resize", update);
    return () => {
      window.removeEventListener("scroll", update);
      window.removeEventListener("resize", update);
    };
  }, [hideOnOrder, path]);

  useEffect(() => {
    if (!open) return;
    const onKey = (event: KeyboardEvent) => {
      if (event.key === "Escape") setOpen(false);
    };
    const onPointer = (event: PointerEvent) => {
      if (!rootRef.current?.contains(event.target as Node)) setOpen(false);
    };
    document.addEventListener("keydown", onKey);
    document.addEventListener("pointerdown", onPointer);
    return () => {
      document.removeEventListener("keydown", onKey);
      document.removeEventListener("pointerdown", onPointer);
    };
  }, [open]);

  if (hideOnOrder || !visible) return null;

  return (
    <div ref={rootRef} className={`fab-dock${open ? " is-open" : ""}`}>
      <div className="fab-bubble" id={menuId}>
        <a className="fab-item" href={askHref} onClick={() => setOpen(false)}>
          <Icon name="mail" />
          Ask
        </a>
        <a className="fab-item fab-item-order" href="/order/" onClick={() => setOpen(false)}>
          <Icon name="capsule" />
          Order · {site.priceLabel}
        </a>
        <a className="fab-item" href="/refund-policy/" onClick={() => setOpen(false)}>
          <Icon name="card" />
          Refunds
        </a>
        <a className="fab-item" href="/cancel-plan/" onClick={() => setOpen(false)}>
          <Icon name="clock" />
          Cancel monthly
        </a>
      </div>
      <div className="fab-launch">
        <button
          type="button"
          className="fab-hint"
          tabIndex={open ? -1 : 0}
          onClick={() => setOpen(true)}
        >
          Need something?
        </button>
        <button
          type="button"
          className="fab fab-toggle"
          aria-expanded={open}
          aria-controls={menuId}
          aria-label={open ? "Close quick actions" : "Open quick actions"}
          onClick={() => setOpen((value) => !value)}
        >
          <span className="fab-plus" aria-hidden="true" />
        </button>
      </div>
    </div>
  );
}
