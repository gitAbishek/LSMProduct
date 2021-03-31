import tw from 'tailwind-styled-components';

export const Card = tw.div`
  mto-card
  mto-border
  mto-border-gray-200
  mto-rounded-sm
  mto-mb-4
`;

export const CardHeader = tw.header`
  mto-card-header
  mto-p-2
  mto-flex
  mto-items-center
  mto-justify-between
`;

export const CardContent = tw.div`
  mto-card-content
  mto-p-4
`;

export const CardHeading = tw.div`
  mto-card-heading
  mto-flex
  mto-items-center
  mto-text-gray-600
`;

export const CardActions = tw.div`
  mto-card-actions
  mto-flex
  mto-items-center
  mto-justify-end
`;
