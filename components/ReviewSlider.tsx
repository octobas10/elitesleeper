"use client";

import { useState } from "react";
import { reviews } from "@/lib/site";

export function ReviewSlider() {
  const [index, setIndex] = useState(0);
  const count = reviews.length;
  const go = (step: number) => setIndex((current) => (current + step + count) % count);

  return (
    <>
      <div className="review-controls" role="group" aria-label="Customer notes">
        <button type="button" className="slider-btn" onClick={() => go(-1)}>
          Previous
        </button>
        <p className="slider-count">
          {index + 1} of {count}
        </p>
        <button type="button" className="slider-btn" onClick={() => go(1)}>
          Next
        </button>
      </div>
      <div className="review-viewport" aria-live="polite">
        <div className="review-track" style={{ transform: `translateX(-${index * 100}%)` }}>
          {reviews.map((review) => (
            <figure key={review.name} className="quote">
              <blockquote>
                <p>{review.quote}</p>
              </blockquote>
              <figcaption>
                <strong>{review.name}</strong>
                <span>{review.detail}</span>
              </figcaption>
            </figure>
          ))}
        </div>
      </div>
    </>
  );
}
