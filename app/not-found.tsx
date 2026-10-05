import { Photo } from "@/components/Blocks";

export default function NotFound() {
  return (
    <main id="main" className="page">
      <div className="wrap prose">
        <p className="eyebrow">404</p>
        <h1>That page is not here.</h1>
        <Photo src="/images/stock/night-bed.jpg" alt="A person sleeping under a light blue comforter" caption="The bottle is still on the home page." />
        <p>The bottle, the directions, and the order form are still up.</p>
        <p>
          <a className="btn" href="/">
            Go to the home page
          </a>
        </p>
      </div>
    </main>
  );
}
