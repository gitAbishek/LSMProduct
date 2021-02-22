import styled from 'styled-components';

interface StyledProps {
	flex?: number;
	direction?:
		| 'row'
		| 'row-reverse'
		| 'column'
		| 'column-reverse'
		| 'initial'
		| 'inherit';
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
const Flex = styled.div`
	display: flex;
	flex: ${(props: StyledProps) => props.flex || null};
	flex-direction: ${(props: StyledProps) => props.direction || 'column'};
	align-items: ${(props: StyledProps) => props.align || null};
	justify-content: ${(props: StyledProps) => props.justify || null};
	flex-wrap: ${(props: StyledProps) => props.wrap || null};
	align-self: ${(props: StyledProps) => props.alignSelf || null};
`;

export default Flex;
