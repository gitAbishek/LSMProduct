import {
	ActionContainer,
	ContentContainer,
	ContentHeader,
	ContentIcon,
	ContentTitle,
} from './styled';
import { AlignLeft, Trash } from 'Icons';

import Button from 'Components/common/Button';
import DragHandle from '../components/DragHandle';
import Dropdown from 'rc-dropdown';
import DropdownOverlay from 'Components/common/DropdownOverlay';
import FlexRow from 'Components/common/FlexRow';
import Icon from 'Components/common/Icon';
import OptionButton from 'Components/common/OptionButton';
import PropTypes from 'prop-types';
import { React } from '@wordpress/element';

const Lesson = (props) => {
	const { id, title, index } = props;
	return (
		<ContentContainer>
			<ContentHeader>
				<FlexRow>
					<DragHandle />
					<ContentIcon icon={<AlignLeft />} />
					<ContentTitle>{title}</ContentTitle>
				</FlexRow>
				<FlexRow>
					<ActionContainer>
						<Button>Edit</Button>
						<Dropdown
							trigger={'click'}
							placement={'bottomRight'}
							animation={'slide-up'}
							overlay={
								<DropdownOverlay>
									<ul>
										<li>
											<Icon icon={<Trash />} />
											Delete
										</li>
									</ul>
								</DropdownOverlay>
							}>
							<OptionButton />
						</Dropdown>
					</ActionContainer>
				</FlexRow>
			</ContentHeader>
		</ContentContainer>
	);
};

Lesson.propTypes = {
	id: PropTypes.string,
	title: PropTypes.string,
	index: PropTypes.number,
};

export default Lesson;
