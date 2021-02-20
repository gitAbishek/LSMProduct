import styled from 'styled-components';

interface StyledProps {
	flex?: number;
	width?: string;
	justify?:
		| 'flex-start'
		| 'flex-end'
		| 'center'
		| 'space-between'
		| 'space-around'
		| 'space-evenly'
		| 'initial'
		| 'inherit';
	align?:
		| 'strecth'
		| 'center'
		| 'flex-start'
		| 'flex-end'
		| 'baseline'
		| 'initial'
		| 'inherit';
	wrap?: 'wrap' | 'nowrap';
	alignSelf?:
		| 'auto'
		| 'strecth'
		| 'center'
		| 'flex-start'
		| 'flex-end'
		| 'baseline'
		| 'initial'
		| 'inherit';
}

const FlexRow = styled.div`
	display: flex;
	flex: ${(props: StyledProps) => props.flex};
	width: ${(props: StyledProps) => props.width};
	justify-content: ${(props: StyledProps) => props.justify || 'inherit'};
	align-items: ${(props: StyledProps) => props.align || 'center'};
	flex-wrap: ${(props: StyledProps) => props.wrap || 'nowrap'};
`;

export default FlexRow;
