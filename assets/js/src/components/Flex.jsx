import styled from 'styled-components';

export default styled.div`
	display: flex;
	flex: ${(props) => props.flex};
	flex-direction: ${(props) => props.direction || 'column'};
	align-items: ${(props) => props.align || null};
	justify-content: ${(props) => props.justify || null};
	flex-wrap: ${(props) => props.wrap || null};
	align-self: ${(props) => props.alignSelf || null};
`;
