import styled from 'styled-components';

const FlexRow = styled.div`
	display: flex;
	flex: ${(props) => props.flex};
	width: ${(props) => props.width};
	justify-content: ${(props) => props.justify || 'inherit'};
	align-items: ${(props) => props.align || 'center'};
	flex-wrap: ${(props) => props.wrap || 'nowrap'};
`;

export default FlexRow;
