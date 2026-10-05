import type { ReactNode } from "react";
import { ProductShot } from "./Blocks";
import { SidebarContactForm } from "./Forms";
import { posts, site } from "@/lib/site";

export function OutLink({ href, children }: { href: string; children: ReactNode }) {
  return (
    <a href={href} target="_blank" rel="noopener noreferrer">
      {children}
    </a>
  );
}

export function BlogFrame({
  returnTo,
  currentSlug,
  toc,
  children,
}: {
  returnTo: string;
  currentSlug?: string;
  toc?: { id: string; label: string }[];
  children: ReactNode;
}) {
  const others = posts.filter((post) => post.slug !== currentSlug);

  return (
    <main id="main" className="page">
      <div className="wrap blog-layout">
        <article className="prose">{children}</article>
        <aside className="blog-rail" aria-label="Related">
          <div className="blog-side">
          {toc && toc.length > 0 ? (
            <nav className="side-block" aria-label="On this page">
              <h2>On this page</h2>
              <ol className="toc-list">
                {toc.map((item) => (
                  <li key={item.id}>
                    <a href={`#${item.id}`}>{item.label}</a>
                  </li>
                ))}
              </ol>
            </nav>
          ) : null}
          <div className="side-block side-product">
            <ProductShot size="side" />
            <p className="side-price">{site.priceLabel}</p>
            <p>60 capsules. About $1.00 a night.</p>
            <a className="btn btn-wide" href="/order/">
              Order the bottle
            </a>
          </div>
          <div className="side-block">
            <h2>Ask the desk</h2>
            <p>A question about the dose, shipping, or a bottle you already ordered.</p>
            <SidebarContactForm returnTo={returnTo} />
          </div>
          {currentSlug ? (
            <div className="side-block">
              <h2>More from the blog</h2>
              <ul className="side-links">
                {others.map((post) => (
                  <li key={post.slug}>
                    <a href={`/blog/${post.slug}/`}>{post.label}</a>
                  </li>
                ))}
                <li>
                  <a href="/blog/">All posts</a>
                </li>
              </ul>
            </div>
          ) : null}
          {currentSlug ? (
            <div className="side-block">
              <h2>Useful pages</h2>
              <ul className="side-links">
                <li>
                  <a href="/#how">How to take it</a>
                </li>
                <li>
                  <a href="/#ingredients">Ingredient list</a>
                </li>
                <li>
                  <a href="/#faq">Questions</a>
                </li>
                <li>
                  <a href="/#reviews">Customer notes</a>
                </li>
                <li>
                  <a href="/shipping/">Shipping</a>
                </li>
                <li>
                  <a href="/#contact">Contact</a>
                </li>
              </ul>
            </div>
          ) : null}
          </div>
        </aside>
      </div>
    </main>
  );
}
