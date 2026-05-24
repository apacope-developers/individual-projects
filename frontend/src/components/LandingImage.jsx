import { useState } from 'react';
import { Stethoscope } from 'lucide-react';

const LandingImage = ({ src, alt, className = '' }) => {
  const [failed, setFailed] = useState(false);

  if (failed) {
    return (
      <div
        className={`flex items-center justify-center bg-gradient-to-br from-primary-600 to-primary-800 ${className}`}
        role="img"
        aria-label={alt}
      >
        <Stethoscope className="w-16 h-16 text-white/60" />
      </div>
    );
  }

  return (
    <img
      src={src}
      alt={alt}
      className={className}
      loading="lazy"
      onError={() => setFailed(true)}
    />
  );
};

export default LandingImage;
