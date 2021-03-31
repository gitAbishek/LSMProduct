import { BiDuplicate, BiImageAdd, BiTrash } from 'react-icons/bi';
import {
	Card,
	CardActions,
	CardHeader,
	CardHeading,
} from 'Components/layout/Card';
import React, { useState } from 'react';

import DragHandle from '../../sections/components/DragHandle';
import Icon from 'Components/common/Icon';
import Input from 'Components/common/Input';

export interface AnswerTrueFalseProps {}

const AnswerTrueFalse: React.FC<AnswerTrueFalseProps> = () => {
	const [firstChecked, setFirstChecked] = useState(false);
	const [secondChecked, setSecondChecked] = useState(true);
	return (
		<>
			<Card className={firstChecked && 'mto-border-green-200'}>
				<CardHeader>
					<CardHeading>
						<DragHandle />
						<h5 className="mto-text-base mto-text-gray-800">False</h5>
					</CardHeading>
					<CardActions>
						<input
							type="checkbox"
							checked={firstChecked}
							onClick={() => {
								setFirstChecked(!firstChecked);
								setSecondChecked(!secondChecked);
							}}
							className="mto-mr-12 mto-p-2.5 mto-rounded-sm mto-border-gray-200 mto-transition-all"
						/>
						<Icon
							icon={<BiImageAdd />}
							className="mto-text-2xl mto-ml-2 mto-text-gray-500"
						/>
						<Icon
							icon={<BiDuplicate />}
							className="mto-text-2xl mto-ml-2 mto-text-gray-500"
						/>
						<Icon
							icon={<BiTrash />}
							className="mto-text-2xl mto-ml-2 mto-text-gray-500"
						/>
					</CardActions>
				</CardHeader>
			</Card>

			<Card className={secondChecked && 'mto-border-green-200'}>
				<CardHeader>
					<CardHeading>
						<DragHandle />
						<h5 className="mto-text-base mto-text-gray-800">True</h5>
					</CardHeading>
					<CardActions>
						<input
							type="checkbox"
							checked={secondChecked}
							onClick={() => {
								setSecondChecked(!secondChecked);
								setFirstChecked(!firstChecked);
							}}
							className="mto-mr-12 mto-p-2.5 mto-rounded-sm mto-border-gray-200 mto-transition-all"
						/>
						<Icon
							icon={<BiImageAdd />}
							className="mto-text-2xl mto-ml-2 mto-text-gray-500"
						/>
						<Icon
							icon={<BiDuplicate />}
							className="mto-text-2xl mto-ml-2 mto-text-gray-500"
						/>
						<Icon
							icon={<BiTrash />}
							className="mto-text-2xl mto-ml-2 mto-text-gray-500"
						/>
					</CardActions>
				</CardHeader>
			</Card>
		</>
	);
};

export default AnswerTrueFalse;
