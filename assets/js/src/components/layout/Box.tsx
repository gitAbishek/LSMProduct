import tw from 'tailwind-styled-components';

export const Box = tw.section`
	mto-box-container
	mto-rounded-sm
	mto-bg-white
	mto-shadow-lg
	mto-mt-10
`;

export const BoxHeader = tw.header`
	mto-box-header
	mto-p-10
	mto-flex
	mto-justify-between
	mto-items-center
`;

export const BoxContent = tw.div`
	mto-box-content
	mto-px-10
`;

export const BoxFooter = tw.footer`
	mto-box-footer
	mto-p-10
`;

export const BoxTitle = tw.h2`
	mto-text-lg
	mto-font-semibold
	mto-text-gray-700
`;

export default Box;
