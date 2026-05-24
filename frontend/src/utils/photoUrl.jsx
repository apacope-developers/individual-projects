const API_ORIGIN = import.meta.env.VITE_API_URL
  ? import.meta.env.VITE_API_URL.replace('/api', '')
  : (import.meta.env.DEV ? '' : 'http://localhost:5000');

export const getProfilePhotoUrl = (profilePhoto) => {
  if (!profilePhoto) return null;
  if (profilePhoto.startsWith('http')) return profilePhoto;
  return `${API_ORIGIN}${profilePhoto}`;
};

export const ProfileAvatar = ({ photo, name, size = 'md', className = '' }) => {
  const sizeClasses = {
    sm: 'w-10 h-10',
    md: 'w-16 h-16',
    lg: 'w-24 h-24',
  };
  const url = getProfilePhotoUrl(photo);
  const initials = name?.split(' ').map((n) => n[0]).join('').slice(0, 2).toUpperCase() || '?';

  if (url) {
    return (
      <img
        src={url}
        alt={name || 'Profile'}
        className={`${sizeClasses[size]} rounded-full object-cover ${className}`}
      />
    );
  }

  return (
    <div
      className={`${sizeClasses[size]} rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center text-primary-700 dark:text-primary-200 font-semibold ${className}`}
    >
      {initials}
    </div>
  );
};
