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

export interface AnswerTrueFalseProps {}

const AnswerMultiChoice: React.FC<AnswerTrueFalseProps> = () => {
	return (
		<>
			<Card>
				<CardHeader>
					<CardHeading>
						<DragHandle />
						<h5 className="mto-text-base mto-text-gray-800">False</h5>
					</CardHeading>
					<CardActions>
						<input
							type="checkbox"
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

			<Card>
				<CardHeader>
					<CardHeading>
						<DragHandle />
						<h5 className="mto-text-base mto-text-gray-800">True</h5>
					</CardHeading>
					<CardActions>
						<input
							type="checkbox"
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
			<Card>
				<CardHeader>
					<CardHeading>
						<DragHandle />
						<h5 className="mto-text-base mto-text-gray-800">True</h5>
					</CardHeading>
					<CardActions>
						<input
							type="checkbox"
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

export default AnswerMultiChoice;
